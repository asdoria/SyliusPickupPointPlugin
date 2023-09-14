import { createSlice } from '@reduxjs/toolkit'

export const modalStore = createSlice({
    name: 'modal',
    initialState: {
        modalOpened: false,
    },
    reducers: {
        setModalOpened: (state, { payload: bool }) => {
            state.modalOpened = bool
        }
    }
})

export const { setModalOpened } = modalStore.actions

export default modalStore.reducer
