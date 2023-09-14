import { configureStore } from '@reduxjs/toolkit'
import mainReducer from './mainStore'
import searchReducer from './searchStore'
import filterReducer from './filterStore'
import pointsReducer from './pointsStore'
import modalReducer from './modalStore'
import topBarNavReducer from './topBarNavStore'

export default configureStore({
    reducer: {
        main: mainReducer,
        search: searchReducer,
        filter: filterReducer,
        points: pointsReducer,
        modal: modalReducer,
        topBarNav: topBarNavReducer,
    }
})
