import React from 'react'
import ReactDOM from 'react-dom/client'
import { Provider } from 'react-redux'
import store from './store'
import App from './components/App'
import './utils/eventBusVanilla'
import { EVENT_INSTANCE_PICKUP_POINT, EVENT_HIDE_PICKUP_POINT } from './utils/constants'
import axios from 'axios'
import 'intl-messageformat'
import Translator from 'bazinga-translator'
import Routing from '../../../../../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min'
import markerIconPath from '../images/marker-icon.png'

const loadTranslations = async (locale) => {
    let translationData = [];
    let routingData     = [];
    const lang         = document.documentElement.lang;

    window.Translator   = Translator;
    window.Routing      = Routing;

    try {
        await Promise.all([
            new Promise((resolve) => {
                axios.get('/js/fos_js_routes.json').then(({ data }) => {
                    routingData = data;
                    resolve();
                });
            }),
            new Promise((resolve) => {
                axios.get(`/${ locale }/translations/messages.json?locales=${ lang }`)
                    .then(({ data }) => {
                        translationData = data;
                        resolve();
                    });
            }),
        ]);
        window.Routing.setRoutingData(routingData);
        window.Translator.fromJSON(translationData);
    } catch (e) {
        // TODO: handle fail
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    const el          = document.querySelector('.react-pickup-point')
    const inputHidden = document.querySelector('.react-pickup-point-input-hidden')

    if (!el || !inputHidden || !el.dataset?.pkpLocale) return
    loadTranslations(el.dataset.pkpLocale)

    window.asdoriaPickupEventBus.addEventListener(EVENT_INSTANCE_PICKUP_POINT, ({ detail }) => {
        if (!detail?.providerCode || !detail?.csrfToken || !detail?.elToTeleport) return

        el.style.display = 'block'

        const routePath = inputHidden.dataset?.routePath ? inputHidden.dataset.routePath : null

        if (!routePath) return

        const instanceReact = ReactDOM.createRoot(el)

        const options = {
            height: el.dataset?.pkpHeight || '400px',
            heightMobile: el.dataset?.pkpHeightMobile || '250px',
            zoom: el.dataset?.pkpZoom || '13',
            markerIcon: el.dataset?.pkpMarkerIcon || markerIconPath,
            filterShop: el.dataset?.pkpFilterShop ? !!Number(el.dataset?.pkpFilterShop) : 0,
            zoomControl: el.dataset?.pkpZoomControl ? !!Number(el.dataset?.pkpZoomControl) : 1,
            scrollWheelZoom: el.dataset?.pkpScrollWheelZoom ? !!Number(el.dataset?.pkpScrollWheelZoom) : 0,
            zoomControlPosition: el.dataset?.pkpZoomControlPosition || 'bottomright',
        }

        instanceReact.render(
            <Provider store={ store }>
                <App routePath={ routePath }
                     csrfToken={ detail.csrfToken }
                     providerCode={ detail.providerCode }
                     defaultPoint={ inputHidden.value || null }
                     options={ options }
                     elToTeleport={ detail.elToTeleport }/>
            </Provider>
        )
    })

    window.asdoriaPickupEventBus.addEventListener(EVENT_HIDE_PICKUP_POINT, () => {
        const elsTeleportable = [...document.querySelectorAll('.react-teleport-pickup-point')]

        elsTeleportable.forEach(elTeleportable => {
            elTeleportable.style.display = 'none'
        })
    })
})
