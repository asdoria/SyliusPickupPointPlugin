import { createSlice } from '@reduxjs/toolkit'
import fetchPoints from '../api/points'
import { STATUS_LOADING, STATUS_SUCCEEDED, STATUS_FAILED } from '../utils/constants'
import matchStrings from '../utils/matchStrings'

export const pointsStore = createSlice({
    name: 'points',
    initialState: {
        points: [],
        pointsFiltered: [],
        currentPoint: {},
        centerLatLng: [],
        status: '',
        error: null,
        pointClicked: false,
        enableDefaultPoint: false,
    },
    reducers: {
        setPoints: (state, { payload: newPoints }) => {
            state.points = newPoints
        },
        setPointsFiltered: (state, { payload: filterValue }) => {
            state.pointsFiltered = [...state.points].filter(point => {
                return matchStrings(point.name, filterValue) || matchStrings(point.full_address, filterValue) || filterValue === ''
            })
        },
        setCurrentPoint: (state, { payload: currentPoint }) => {
            state.currentPoint = currentPoint
        },
        resetCurrentPoint: (state) => {
            state.currentPoint = {}
        },
        setPointClicked: (state, { payload: bool }) => {
            state.pointClicked = bool
        },
        setEnableDefaultPoint: (state, { payload: bool }) => {
            state.enableDefaultPoint = bool
        },
    },
    extraReducers (builder) {
        builder
            .addCase(fetchPoints.pending, (state, { payload }) => {
                state.status = STATUS_LOADING
            })
            .addCase(fetchPoints.fulfilled, (state, { payload: newPoints }) => {
                if (!newPoints.length) {
                    state.status = STATUS_FAILED
                    return
                }

                state.points = newPoints

                state.centerLatLng = [state.points[0].latitude, state.points[0].longitude]

                state.status = STATUS_SUCCEEDED
            })
            .addCase(fetchPoints.rejected, (state, { error }) => {
                state.status = STATUS_FAILED
                state.error  = error.message
            })
    }
})

export const {
                 setPoints,
                 setCurrentPoint,
                 resetCurrentPoint,
                 setPointClicked,
                 setPointsFiltered,
                 setEnableDefaultPoint,
             } = pointsStore.actions

export default pointsStore.reducer
