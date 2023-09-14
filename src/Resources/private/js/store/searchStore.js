import { createSlice } from '@reduxjs/toolkit'

export const searchStore = createSlice({
    name: 'search',
    initialState: {
        searchValue: '',
    },
    reducers: {
        setSearchValue: (state, { payload: bool }) => {
            state.searchValue = bool
        },
    },
})

export const { setSearchValue } = searchStore.actions

export default searchStore.reducer
