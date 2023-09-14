import React from 'react'
import { useDispatch } from 'react-redux'
import { useMapEvents } from 'react-leaflet'
import { setModalOpened } from '../../store/modalStore'

const MapEvents = () => {
    const dispatch = useDispatch()

    const map = useMapEvents({
        click: () => {
            dispatch(setModalOpened(false))
        },
        dragstart: () => {
            dispatch(setModalOpened(false))
        }
    })

    return (
        <></>
    )
}

export default MapEvents
