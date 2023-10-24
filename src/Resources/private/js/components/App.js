import React, { useEffect, useState } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { createPortal } from 'react-dom'
import AppInner from './AppInner'
import { EVENT_SET_PICKUP_POINT, EVENT_UPDATE_CURRENT_PICKUP_POINT, STATUS_SUCCEEDED, STATUS_FAILED } from '../utils/constants'
import { setCrsfToken, setOptions, setProviderCode, setRoutePath } from '../store/mainStore'
import { resetCurrentPoint, setCurrentPoint, setEnableDefaultPoint, setPoints } from '../store/pointsStore'
import { setModalOpened } from '../store/modalStore'
import fetchPoints from '../api/points'
import EventBusVanilla from '../utils/eventBusVanilla'

const App = ({ routePath, csrfToken, providerCode, options, defaultPoint, elToTeleport }) => {
    const dispatch = useDispatch()

    const [getElToTeleport, setElTeleported] = useState(elToTeleport)

    const getPoints          = useSelector(state => state.points.points)
    const getStatusRequest   = useSelector(state => state.points.status)
    const getCurrentPoint    = useSelector(state => state.points.currentPoint)
    const enableDefaultPoint = useSelector(state => state.points.enableDefaultPoint)
    const modalOpened        = useSelector(state => state.modal.modalOpened)
    const getCrsfToken       = useSelector(state => state.main.csrfToken)
    const getProviderCode    = useSelector(state => state.main.providerCode)
    const getOptions         = useSelector(state => state.main.options)

    const initDefaultPoint = () => {
        const currentPoint = getPoints.find(point => point.code === defaultPoint)
        if (!currentPoint) return

        dispatch(setCurrentPoint(currentPoint))
        dispatch(setEnableDefaultPoint(false))
    }

    /**
     * Observe status of request.
     * Fetch points and init default point in one case
     */
    useEffect(() => {
        if (!getStatusRequest) {
            dispatch(fetchPoints({ routePath, csrfToken, providerCode }))
            return
        }

        if (getStatusRequest === STATUS_SUCCEEDED && enableDefaultPoint) {
            initDefaultPoint()
        }

        if (getStatusRequest === STATUS_FAILED) {
            dispatch(setPoints([]))
        }
    }, [getStatusRequest, dispatch])

    /**
     * Observe state of currentPoint.
     * Dispatch currentPoint.
     */
    useEffect(() => {
        EventBusVanilla.dispatchEvent(EVENT_UPDATE_CURRENT_PICKUP_POINT, { ...getCurrentPoint })
    }, [getCurrentPoint])

    /**
     * On component mounted : init states
     */
    useEffect(() => {
        dispatch(setOptions(options))
        dispatch(setRoutePath(routePath))
        dispatch(setCrsfToken(csrfToken))
        dispatch(setProviderCode(providerCode))

        EventBusVanilla.addEventListener(EVENT_SET_PICKUP_POINT, ({ detail }) => {
            if (!detail?.providerCode && !detail?.csrfToken && !detail?.elToTeleport) return

            detail.elToTeleport.style.display = 'block'

            setElTeleported(detail.elToTeleport)
            dispatch(setProviderCode(detail.providerCode))
            dispatch(setCrsfToken(detail.csrfToken))
            dispatch(resetCurrentPoint())
            dispatch(setModalOpened(false))
            dispatch(fetchPoints({ routePath, csrfToken: detail.csrfToken, providerCode: detail.providerCode }))
            dispatch(setEnableDefaultPoint(true))
        })
    }, [])

    if (!getElToTeleport) return

    return (
        <>
            { createPortal(<AppInner/>, getElToTeleport) }
        </>
    )
}

export default App
