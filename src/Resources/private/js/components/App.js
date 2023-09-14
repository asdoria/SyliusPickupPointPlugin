import React, { useEffect, useState } from 'react'
import { useDispatch } from 'react-redux'
import { createPortal } from 'react-dom'
import EventBusVanilla from '../utils/eventBusVanilla'
import AppInner from './AppInner'
import { EVENT_SET_PICKUP_POINT } from '../utils/constants'

const App = (props) => {
    const dispatch = useDispatch()

    const [getElToTeleport, setElTeleported] = useState(props.elToTeleport)

    EventBusVanilla.addEventListener(EVENT_SET_PICKUP_POINT, ({ detail }) => {
        if (!detail?.providerCode && !detail?.csrfToken && !detail?.elToTeleport) return

        detail.elToTeleport.style.display = 'block'

        setElTeleported(detail.elToTeleport)
    })

    if(!getElToTeleport) return

    return (
        <>
            { createPortal(<AppInner { ...props }/>, getElToTeleport) }
        </>
    )
}

export default App
