import axios from "axios"

export async function getAPIs(
    apiRoute: string, 
    filter?: string, 
    page?: number, 
    pagination?: number, 
    orderBy?: string, 
    sorting?: string, 
    extraConds?: {key: string, value: string}[]
) {

    if (!filter) filter = ''
    if (!pagination) pagination = 10
    if (!page) page = 1
    if (!sorting) sorting = 'asc'
    if (!orderBy) orderBy = 'id'
    if (!extraConds) extraConds = []

    let extraCond = ''
    extraConds.forEach(element => extraCond += '&' + element.key + '=' + element.value)

    const url = `/api/${apiRoute}?page=${page}&per_page=${pagination}&order_by=${orderBy}&order_direction=${sorting}&filter=${filter}${extraCond}`

    const apiClient = axios.create({
        withCredentials: true,
        withXSRFToken: true
    })

    const response = await apiClient.get(url)

    return {
        rows: response.data.data,
        meta: {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            per_page: response.data.per_page,
            total: response.data.total,
        }
    }
}

export async function postAPIs(
    apiRoute: string,
    data: any
) {
    const url = `/api/${apiRoute}`

    const apiClient = axios.create({
        withCredentials: true,
        withXSRFToken: true
    })

    const response = await apiClient.post(url, data)

    return response.data
}

export async function putAPIs(
    apiRoute: string,
    data: any
) {
    const url = `/api/${apiRoute}`

    const apiClient = axios.create({
        withCredentials: true,
        withXSRFToken: true
    })

    const response = await apiClient.put(url, data)

    return response.data
}

export async function deleteAPIs(
    apiRoute: string,
    id: number
) {
    const url = `/api/${apiRoute}/${id}`

    const apiClient = axios.create({
        withCredentials: true,
        withXSRFToken: true
    })

    const response = await apiClient.delete(url)

    return response.data
}

export function useAPIs() {
    return { getAPIs, postAPIs, putAPIs, deleteAPIs };
}
