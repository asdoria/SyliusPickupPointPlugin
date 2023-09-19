import React, { useEffect, useState } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { resetCurrentPoint, setCurrentPoint } from '../store/pointsStore'
import { setModalOpened } from '../store/modalStore'
import { setCrsfToken, setOptions, setProviderCode, setRoutePath } from '../store/mainStore'
import {
    EVENT_SET_PICKUP_POINT,
    EVENT_UPDATE_CURRENT_PICKUP_POINT,
    STATUS_FAILED,
    STATUS_SUCCEEDED
} from '../utils/constants'
import fetchPoints from '../api/points'
import EventBusVanilla from '../utils/eventBusVanilla'
import { isTablet } from '../utils/viewport'
import AppInnerDesktop from './Desktop/AppInnerDesktop'
import AppInnerMobile from './Mobile/AppInnerMobile'

const AppInner = ({ routePath, csrfToken, providerCode, options, defaultPoint }) => {
    const dispatch = useDispatch()

    const [enableDefaultPoint, setEnableDefaultPoint] = useState(true)

    const getPoints        = useSelector(state => state.points.points)
    const getStatusRequest = useSelector(state => state.points.status)
    const getCurrentPoint  = useSelector(state => state.points.currentPoint)
    const modalOpened      = useSelector(state => state.modal.modalOpened)
    const getCrsfToken     = useSelector(state => state.main.csrfToken)
    const getProviderCode  = useSelector(state => state.main.providerCode)
    const getOptions       = useSelector(state => state.main.options)

    const initDefaultPoint = () => {
        const currentPoint = getPoints.find(point => point.code === defaultPoint)
        if (!currentPoint) return

        dispatch(setCurrentPoint(currentPoint))
        setEnableDefaultPoint(false)
    }

    /**
     * On component mounted : init states
     */
    useEffect(() => {
        dispatch(setOptions(options))
        dispatch(setRoutePath(routePath))
        dispatch(setCrsfToken(csrfToken))
        dispatch(setProviderCode(providerCode))

        EventBusVanilla.addEventListener(EVENT_SET_PICKUP_POINT, ({ detail }) => {
            if (!detail?.providerCode && !detail?.csrfToken && !detail?.elToTeleport) return

            dispatch(setProviderCode(detail.providerCode))
            dispatch(setCrsfToken(detail.csrfToken))
            dispatch(resetCurrentPoint())
            dispatch(setModalOpened(false))
            dispatch(fetchPoints({ routePath, csrfToken: detail.csrfToken, providerCode: detail.providerCode }))
            setEnableDefaultPoint(true)
        })
    }, [])

    /**
     * Observe status of request.
     * Fetch points and init default point in one case
     */
    useEffect(() => {
        if (!getStatusRequest) {
            dispatch(fetchPoints({ routePath, csrfToken, providerCode }))
            return
        }

        if (getStatusRequest === STATUS_SUCCEEDED && enableDefaultPoint) {
            initDefaultPoint()
        }
    }, [getStatusRequest, dispatch])

    /**
     * Observe state of currentPoint.
     * Dispatch currentPoint.
     */
    useEffect(() => {
        EventBusVanilla.dispatchEvent(EVENT_UPDATE_CURRENT_PICKUP_POINT, { ...getCurrentPoint })
    }, [getCurrentPoint])

    /**
     * Render
     */
    if (getStatusRequest === STATUS_SUCCEEDED || getStatusRequest === STATUS_FAILED) {
        return (
            <>
                { isTablet() && <AppInnerMobile/> }
                { !isTablet() && <AppInnerDesktop/> }
            </>
        )
    }

    return (
        <div className="Loader-block"
             style={ { height: isTablet() ? getOptions.heightMobile : getOptions.height } }></div>
    )
}

export default AppInner
