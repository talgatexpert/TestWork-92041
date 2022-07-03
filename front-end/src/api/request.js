import http from "@/utils/request";

export const sanctum = async () => await http.get('/sanctum/csrf-cookie')
