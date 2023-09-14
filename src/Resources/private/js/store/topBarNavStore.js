import { createSlice } from '@reduxjs/toolkit'

export const topBarNavStore = createSlice({
    name: 'topBarNav',
    initialState: {
        mapVisible: true,
    },
    reducers: {
        setMapVisible: (state, { payload: bool }) => {
            console.log(state.mapVisible);
            state.mapVisible = bool
            console.log(state.mapVisible);
        }
    }
})

export const { setMapVisible } = topBarNavStore.actions

export default topBarNavStore.reducer
