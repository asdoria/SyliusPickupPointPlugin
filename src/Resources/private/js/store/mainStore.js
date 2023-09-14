import { createSlice } from '@reduxjs/toolkit'

export const mainStore = createSlice({
    name: 'main',
    initialState: {
        csrfToken: '',
        providerCode: null,
        postCode: null,
        options: {},
        routePath: null,
    },
    reducers: {
        setCrsfToken: (state, { payload: newToken }) => {
            state.csrfToken = newToken
        },
        setProviderCode: (state, { payload: newProviderCode }) => {
            state.providerCode = newProviderCode
        },
        setPostCode: (state, { payload: postCode }) => {
            state.postCode = postCode
        },
        setRoutePath: (state, { payload: routePath }) => {
            state.routePath = routePath
        },
        setOptions: (state, { payload: options }) => {
            state.options = { ...options }
        },
    },
})

export const {
                 setCrsfToken,
                 setProviderCode,
                 setRoutePath,
                 setPostCode,
                 setOptions
             } = mainStore.actions

export default mainStore.reducer
