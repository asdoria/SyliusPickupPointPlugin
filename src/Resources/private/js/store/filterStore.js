import { createSlice } from '@reduxjs/toolkit'

export const filterStore = createSlice({
    name: 'filter',
    initialState: {
        filterValue: '',
        filterInputValue: '',
    },
    reducers: {
        setFilterValue: (state, { payload: bool }) => {
            state.filterValue = bool
        },
        setFilterInputValue: (state, { payload }) => {
            state.filterInputValue = payload
        },
    },
})

export const { setFilterValue, setFilterInputValue } = filterStore.actions

export default filterStore.reducer
