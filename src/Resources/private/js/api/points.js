import axios from 'axios'
import { createAsyncThunk } from '@reduxjs/toolkit'

const fetchPoints = createAsyncThunk('points/pointsStore', async ({
    routePath = '',
    providerCode,
    csrfToken,
    postCode = null
}) => {
    const params = {
        providerCode,
        _csrf_token: csrfToken,
    }

    if (postCode) {
        params.postCode = postCode
    }

    const response = await axios.get(routePath, { params })

    return response.data.map(point => {
        point.longitude = Number(point.longitude)
        point.latitude  = Number(point.latitude)

        return point
    })
})

export default fetchPoints
