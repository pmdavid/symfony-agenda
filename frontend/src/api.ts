export const API_URL = "http://localhost:8080/api";

export interface CommonArea {
    id: number;
    name: string;
}

export interface Slot {
    hour: number;
    status: "LIBRE" | "RESERVADO";
}


// Llamar a nuestro GET /availability para obtener todos los slots por fecha y área común
export async function fetchAvailability(commonAreaId: number, date: string): Promise<Slot[]> {
    const res = await fetch(`${API_URL}/availability?commonArea=${commonAreaId}&date=${date}`);
    const data = await res.json();
    return data.slots; // Asegúrate de que el backend devuelve { slots: [...] }
}

// Llamar a nuestro POST /reserve para reservar un slot especifico
export async function bookSlot(commonAreaId: number, date: string, hour: number): Promise<boolean> {
    const res = await fetch(`${API_URL}/reserve`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ commonArea: commonAreaId, date, hour })
    });
    const data = await res.json();
    return data.success;
}
