import React from 'react'
import { useDispatch, useSelector } from 'react-redux'
import ModalPoint from './ModalPoint'
import { setModalOpened } from '../../store/modalStore'

const ModalButtonToggle = () => {
    const dispatch = useDispatch()

    const modalOpened = useSelector(state => state.modal.modalOpened)

    /**
     *
     * @param event
     */
    const handleModal = (event) => {
        dispatch(setModalOpened(!modalOpened))
    }

    return (
        <div onClick={ handleModal } className="pkp-button-toggle-modal">
            <span className="pkp-button-toggle-modal__label">
                { Translator.trans('asdoria_pickup_point.ui.list_shops') }
            </span>
            <span className="pkp-button-toggle-modal__icon">{ modalOpened ? '-' : '+' }</span>
        </div>
    )
}

export default ModalButtonToggle
