import React, { useCallback, useEffect } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { setCurrentPoint } from '../../store/pointsStore'
import { setSearchValue } from '../../store/searchStore'
import { setPostCode } from '../../store/mainStore'
import debounce from '../../utils/debounce'
import fetchPoints from '../../api/points'
import { setModalOpened } from '../../store/modalStore'

const Search = () => {
    const dispatch        = useDispatch()
    const searchValue     = useSelector(state => state.search.searchValue)
    const getCrsfToken    = useSelector(state => state.main.csrfToken)
    const getProviderCode = useSelector(state => state.main.providerCode)
    const getRoutePath    = useSelector(state => state.main.routePath)
    const getPostCode     = useSelector(state => state.main.postCode)

    const changeHandler = (event) => {
        dispatch(setSearchValue(event.target.value))
    }

    const validateHandler = (event) => {
        if (!searchValue) return

        dispatch(setPostCode(searchValue))
        dispatch(setCurrentPoint({}))
        dispatch(setModalOpened(false))
        dispatch(fetchPoints({
            routePath: getRoutePath,
            csrfToken: getCrsfToken,
            providerCode: getProviderCode,
            postCode: searchValue,
        }))
        dispatch(setSearchValue(''))
    }

    return (
        <div className="pkp-input pkp-input--search"
             style={ { display: 'flex', flexWrap: 'wrap', alignItems: 'center' } }>
            <i className="pkp-icon-search" style={ { marginRight: '0.5rem' } }></i>
            <input type="number"
                   className=""
                   style={ { flex: '1 1 0%', background: 'transparent' } }
                   placeholder={ Translator.trans('asdoria_pickup_point.ui.search') }
                   onChange={ changeHandler }/>
            <div onClick={ validateHandler }
                    className={ 'pkp-input-validate' + (searchValue ? ' pkp-input-validate--active' : '') }>
                OK
            </div>
        </div>
    )
}

export default Search
