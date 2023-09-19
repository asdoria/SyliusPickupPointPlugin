import React, { useState } from 'react'
import { useSelector } from 'react-redux'
import { CSSTransition } from 'react-transition-group'
import { STATUS_SUCCEEDED } from '../utils/constants'

const PointSelected = () => {
    const getStatusRequest = useSelector(state => state.points.status)
    const getCurrentPoint  = useSelector(state => state.points.currentPoint)

    const [openingVisibe, setOpeningVisibe] = useState(false)

    /**
     *
     * @param event
     */
    const onClickMoreDetails = (event) => {
        setOpeningVisibe(!openingVisibe)
    }

    return (
        <div className="pkp-point-selected">
            <p className="pkp-point-selected__label">
                { getStatusRequest === STATUS_SUCCEEDED ?
                    Translator.trans('asdoria_pickup_point.ui.pickup_point_selected') + ' :' :
                    Translator.trans('asdoria_pickup_point.ui.no_pickup_point_found')
                }
            </p>
            <CSSTransition in={ !!Object.keys(getCurrentPoint).length }
                           classNames="Animation-opacity" timeout={ 300 }
                           unmountOnExit appear>
                <>
                    <p>{ getCurrentPoint.name }</p>
                    <p>{ getCurrentPoint.full_address }</p>
                    <span className="pkp-point-selected__button" onClick={ onClickMoreDetails }>
                        { openingVisibe ?
                            `${ Translator.trans('asdoria_pickup_point.ui.less_details') } -`
                            : `${ Translator.trans('asdoria_pickup_point.ui.more_details') } +` }
                    </span>
                </>
            </CSSTransition>

            { !!Object.keys(getCurrentPoint).length && openingVisibe &&
                <ul className="">
                    { Object.entries(getCurrentPoint.opening).filter(([day, hour]) => hour).map(([day, hour], index) =>
                        <li className="" key={ index }>
                            { Translator.trans(`asdoria_pickup_point.ui.${ day }`) } : { hour }
                        </li>) }
                </ul>
            }
        </div>
    )
}

export default PointSelected
