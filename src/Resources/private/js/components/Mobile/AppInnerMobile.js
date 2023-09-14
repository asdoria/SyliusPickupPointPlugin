import React from 'react'
import ModalButtonToggle from '../Modal/ModalButtonToggle'
import Search from '../Search/Search'
import Map from '../Map/Map'
import Modal from '../Modal/Modal'
import PointSelected from '../PointSelected'
import TopBarNavMobile from '../Mobile/TopBarNavMobile'
import clickOutside from '../../utils/clickOutside'
import { useDispatch } from 'react-redux'
import { setModalOpened } from '../../store/modalStore'

const AppInnerMobile = () => {
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
            <Search/>

            <TopBarNavMobile/>

            <div className="pkp-map">
                <Map/>
                <Modal/>
            </div>

            <PointSelected/>
        </div>
    )
}

export default AppInnerMobile
