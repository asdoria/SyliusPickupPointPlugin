import React, { useEffect } from 'react'
import { useSelector } from 'react-redux'
import MapMaker from './MapMarker'
import { useMap } from 'react-leaflet'

const MapMarkers = () => {
    const getPoints       = useSelector(state => state.points.points)
    const getCurrentPoint = useSelector(state => state.points.currentPoint)
    const getOptions      = useSelector(state => state.main.options)

    const map = useMap()

    useEffect(() => {
        if (!Object.keys(getCurrentPoint).length) return

        map.setView([getCurrentPoint.latitude, getCurrentPoint.longitude], getOptions.zoom)
    }, [getCurrentPoint])

    return (
        <>
            { getPoints.map((point, index) => <MapMaker point={ point } key={ index }/>) }
        </>
    )
}

export default MapMarkers
