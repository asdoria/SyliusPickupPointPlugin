import React, { useEffect, useState } from 'react'
import { useSelector } from 'react-redux'
import { MapContainer, TileLayer, Marker, Popup, ZoomControl } from 'react-leaflet'
import MapMarkers from './MapMarkers'
import MapEvents from './MapEvents'
import { isTablet } from '../../utils/viewport'
import { STATUS_SUCCEEDED } from '../../utils/constants'

/**
 *
 * @returns {JSX.Element}
 * @constructor
 */
const Map = () => {
    const getStatusRequest = useSelector(state => state.points.status)
    const getCenterLatLng  = useSelector(state => state.points.centerLatLng)
    const getOptions       = useSelector(state => state.main.options)

    const centerOfEurope = [47.751569, 1.675063]
    const zoomForEurope  = 5

    return (
        <MapContainer center={ getStatusRequest === STATUS_SUCCEEDED ? getCenterLatLng : centerOfEurope }
                      zoom={ getStatusRequest === STATUS_SUCCEEDED ? getOptions.zoom : zoomForEurope }
                      scrollWheelZoom={ getOptions.scrollWheelZoom }
                      zoomControl={ false }
                      style={ { height: isTablet() ? getOptions.heightMobile : getOptions.height } }
                      className="pkp-map-container">
            <TileLayer
                attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
            />
            <MapMarkers/>

            { getOptions.zoomControl && !isTablet() && <ZoomControl position={ getOptions.zoomControlPosition }/> }
            <MapEvents/>
        </MapContainer>
    )
}

export default Map
