import React, { useEffect, useState } from 'react'
import { useSelector } from 'react-redux'
import { MapContainer, TileLayer, Marker, Popup, ZoomControl } from 'react-leaflet'
import MapMarkers from './MapMarkers'
import MapEvents from './MapEvents'
import { isTablet } from '../../utils/viewport'

/**
 *
 * @returns {JSX.Element}
 * @constructor
 */
const Map = () => {
    const getCenterLatLng = useSelector(state => state.points.centerLatLng)
    const getOptions      = useSelector(state => state.main.options)

    return (
        <MapContainer center={ getCenterLatLng }
                      zoom={ getOptions.zoom }
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
