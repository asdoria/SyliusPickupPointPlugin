import React, { useEffect, useCallback } from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { setFilterInputValue } from '../../store/filterStore'
import { setPointsFiltered } from '../../store/pointsStore'
import debounce from '../../utils/debounce'
import { CSSTransition } from 'react-transition-group'

const Filter = () => {
    const dispatch         = useDispatch()
    const filterInputValue = useSelector(state => state.filter.filterInputValue)
    const modalOpened      = useSelector(state => state.modal.modalOpened)
    const getOptions       = useSelector(state => state.main.options)

    const changeHandler = (event) => {
        dispatch(setFilterInputValue(event.target.value))
    }

    const debouncedChangeHandler = useCallback(
        debounce(changeHandler, 1000)
    )

    /**
     *
     */
    const onResetFilter = () => {
        dispatch(setFilterInputValue(''))
    }

    useEffect(() => {
        dispatch(setPointsFiltered(filterInputValue))
    }, [filterInputValue, dispatch])

    if (!!!getOptions?.filterShop) return

    return (
        <input type="text"
               className="pkp-input pkp-input--filter"
               placeholder={ Translator.trans('asdoria_pickup_point.ui.filter_shops') }
               onChange={ debouncedChangeHandler }/>
    )
}

export default Filter
