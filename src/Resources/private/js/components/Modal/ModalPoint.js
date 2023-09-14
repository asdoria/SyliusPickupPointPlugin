import React, { useState, useEffect } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { setCurrentPoint, setPointClicked } from '../../store/pointsStore'
import { CSSTransition } from 'react-transition-group'

const ModalPoint = ({ point }) => {
    const dispatch = useDispatch()

    const getCurrentPoint = useSelector(state => state.points.currentPoint)

    const [openingVisibe, setOpeningVisibe]   = useState(false)
    const [openingContent, setOpeningContent] = useState(null)

    /**
     *
     * @param event
     */
    const onClickPoint = (event) => {
        dispatch(setCurrentPoint(point))
        dispatch(setPointClicked(true))
    }

    /**
     *
     * @param event
     */
    const onClickMoreDetails = (event) => {
        event.stopPropagation()
        setOpeningVisibe(!openingVisibe)
    }

    useEffect(() => {
        if (openingVisibe) {
            setOpeningContent(
                Object.entries(point.opening).filter(([day, hour]) => hour).map(([day, hour], index) =>
                    <li className="pkp-modal-point__opening-item" key={ index }>
                        { Translator.trans(`asdoria_pickup_point.ui.${ day }`) } : { hour }
                    </li>)
            )

            return
        }

        setOpeningContent(null)
    }, [openingVisibe])

    return (
        <li onClick={ onClickPoint }
            className={ 'pkp-modal-point' + ( getCurrentPoint.code === point.code ? ' pkp-modal-point--active' : '' ) }>
            <p>{ point.name }</p>
            <p>{ point.full_address }</p>

            <div className="pkp-modal-point__wrapper-button">
                <div onClick={ onClickMoreDetails } className="pkp-modal-point__button">
                    { openingVisibe ?
                        `${Translator.trans('asdoria_pickup_point.ui.less_details')} -`
                        : `${Translator.trans('asdoria_pickup_point.ui.more_details')} +` }
                </div>
            </div>

            <CSSTransition in={ openingVisibe } classNames="Animation-opacity" timeout={ 300 } unmountOnExit appear>
                <ul className="pkp-modal-point__opening-list">{ openingContent }</ul>
            </CSSTransition>
        </li>
    )
}

export default ModalPoint
