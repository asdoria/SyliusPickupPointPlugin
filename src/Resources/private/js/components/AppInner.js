import React, { useEffect } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { resetCurrentPoint, setCurrentPoint, setEnableDefaultPoint } from '../store/pointsStore'
import { setModalOpened } from '../store/modalStore'
import { setCrsfToken, setOptions, setProviderCode, setRoutePath } from '../store/mainStore'
import {
    EVENT_SET_PICKUP_POINT,
    EVENT_UPDATE_CURRENT_PICKUP_POINT,
    STATUS_FAILED,
    STATUS_SUCCEEDED
} from '../utils/constants'
import fetchPoints from '../api/points'
import { isTablet } from '../utils/viewport'
import AppInnerDesktop from './Desktop/AppInnerDesktop'
import AppInnerMobile from './Mobile/AppInnerMobile'

const AppInner = () => {
    const dispatch = useDispatch()

    const getStatusRequest = useSelector(state => state.points.status)
    const getOptions       = useSelector(state => state.main.options)

    /**
     * Render
     */
    if (getStatusRequest === STATUS_SUCCEEDED || getStatusRequest === STATUS_FAILED) {
        return (
            <>
                { isTablet() ? <AppInnerMobile/> : <AppInnerDesktop/> }
            </>
        )
    }

    return (
        <div className="Loader-block"
             style={ { height: isTablet() ? getOptions.heightMobile : getOptions.height } }></div>
    )
}

export default AppInner
