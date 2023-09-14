import React, { useEffect } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { setMapVisible } from '../../store/topBarNavStore'
import { setModalOpened } from '../../store/modalStore'

const TopBarNavMobile = () => {
    const dispatch = useDispatch()

    const mapVisible  = useSelector(state => state.topBarNav.mapVisible)
    const modalOpened = useSelector(state => state.modal.modalOpened)

    /**
     *
     */
    const onClickMap = () => {
        if (mapVisible) return

        dispatch(setMapVisible(true))
        dispatch(setModalOpened(false))
    }

    /**
     *
     */
    const onClickListShops = () => {
        if (!mapVisible) return

        dispatch(setMapVisible(false))
        dispatch(setModalOpened(true))
    }

    return (
        <div className="pkp-mobile-top-bar">
            <div onClick={ onClickMap }
                 className={ 'pkp-mobile-top-bar__item' + ( mapVisible ? ' pkp-mobile-top-bar__item--active' : '' ) }>
                { Translator.trans('asdoria_pickup_point.ui.map') }
            </div>
            <div onClick={ onClickListShops }
                 className={ 'pkp-mobile-top-bar__item' + ( !mapVisible ? ' pkp-mobile-top-bar__item--active' : '' ) }>
                { Translator.trans('asdoria_pickup_point.ui.list_shops') }
            </div>
        </div>
    )
}

export default TopBarNavMobile
