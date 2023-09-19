import React, { useEffect } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { CSSTransition } from 'react-transition-group'
import ModalPoint from './ModalPoint'
import { setModalOpened } from '../../store/modalStore'
import { setPointClicked } from '../../store/pointsStore'
import { ScrollIntoDiv } from '../../utils/scroll-into-div'
import Filter from '../Filter/Filter'

const Modal = () => {
    const dispatch = useDispatch()

    const modalOpened       = useSelector(state => state.modal.modalOpened)
    const getPointsFiltered = useSelector(state => state.points.pointsFiltered)

    const getCurrentPoint = useSelector(state => state.points.currentPoint)
    const pointClicked    = useSelector(state => state.points.pointClicked)

    useEffect(() => {
        if (modalOpened) {
            setTimeout(() => {
                ScrollIntoDiv()
            }, 250)
        }
    }, [modalOpened])

    useEffect(() => {
        if (pointClicked) {
            ScrollIntoDiv()
            dispatch(setPointClicked(false))
        }
    }, [pointClicked])

    return (
        <>
            <CSSTransition in={ modalOpened } classNames="Animation-translateX" timeout={ 300 } unmountOnExit appear>
                <div className="pkp-modal">
                    <Filter/>
                    <ul className="js-pkp-modal-points pkp-modal-points pkp-scrollbar" style={{ listStyleType: 'none' }} data-lenis-prevent>
                        { getPointsFiltered.map((point, index) => <ModalPoint key={ index } point={ point }/>) }
                    </ul>
                </div>
            </CSSTransition>
        </>
    )
}

export default Modal
