import React from 'react'
import ModalButtonToggle from '../Modal/ModalButtonToggle'
import Search from '../Search/Search'
import Map from '../Map/Map'
import Modal from '../Modal/Modal'
import PointSelected from '../PointSelected'
import clickOutside from '../../utils/clickOutside'
import { setModalOpened } from '../../store/modalStore'
import { useDispatch } from 'react-redux'

const AppInnerDesktop = () => {
    const dispatch = useDispatch()

    /**
     *
     */
    const onClickOutside = () => {
        dispatch(setModalOpened(false))
    }

    const appContainerRef = clickOutside(onClickOutside)
    
    return (
        <div className="pkp-app" ref={ appContainerRef }>
            <div className="pkp-top-bar">
                <ModalButtonToggle/>
                <Search/>
            </div>

            <div className="pkp-map">
                <Map/>
                <Modal/>
            </div>

            <PointSelected/>
        </div>
    )
}

export default AppInnerDesktop
