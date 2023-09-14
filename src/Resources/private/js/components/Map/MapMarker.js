import { Marker } from 'react-leaflet/Marker'
import { Popup } from 'react-leaflet/Popup'
import React, { useEffect, useState } from 'react'
import { useSelector, useDispatch } from 'react-redux'
import { setModalOpened } from '../../store/modalStore'
import { setCurrentPoint, setCenterLatLng, setPointClicked } from '../../store/pointsStore'
import * as L from 'leaflet'
import { ScrollIntoDiv } from '../../utils/scroll-into-div'

const MapMarker = ({ point }) => {
    const dispatch = useDispatch()

    const getPoints       = useSelector(state => state.points.points)
    const getCurrentPoint = useSelector(state => state.points.currentPoint)
    const getOptions      = useSelector(state => state.main.options)
    const modalOpened     = useSelector(state => state.modal.modalOpened)

    const [opacity, setOpacity] = useState(1)

    const customIcon = L.icon({
        iconUrl: getOptions.markerIcon,
    })

    /**
     *
     * @param event
     */
    const onClickMarker = (event) => {
        const currentPoint = getPoints.find(point => point.longitude === event.latlng.lng && point.latitude === event.latlng.lat)

        if (currentPoint) {
            dispatch(setCurrentPoint(currentPoint))
        }

        if (modalOpened) {
            dispatch(setPointClicked(true))
        }
    }

    useEffect(() => {
        if (!Object.keys(getCurrentPoint).length) return

        if (getCurrentPoint.longitude === point.longitude && getCurrentPoint.latitude === point.latitude) {
            setOpacity(1)
            return
        }

        setOpacity(0.5)
    }, [getCurrentPoint])

    return (
        <>
            <Marker position={ [point.latitude, point.longitude] } icon={ customIcon } opacity={ opacity }
                    eventHandlers={ { click: onClickMarker } }>
                {/*<Popup>*/}
                {/*    <span>{ point.name }</span>*/}
                {/*    <span>{ point.full_address }</span>*/}
                {/*    <ul className="pkp-map-marker__opening-list">*/}
                {/*        { Object.entries(point.opening).map(([day, hour], index) =>*/}
                {/*            <li key={ index }>{ day } : { hour }</li>)*/}
                {/*        }*/}
                {/*    </ul>*/}
                {/*</Popup>*/}
            </Marker>
        </>
    )
}

export default MapMarker
