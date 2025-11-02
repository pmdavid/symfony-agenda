export const API_URL = "http://localhost:8080/api";

export interface CommonArea {
    id: number;
    name: string;
}

export interface Slot {
    hour: number;
    status: "LIBRE" | "RESERVADO";
}


// Trae los slots de un CommonArea y fecha
export async function fetchAvailability(commonAreaId: number, date: string): Promise<Slot[]> {
    const res = await fetch(`${API_URL}/availability?commonArea=${commonAreaId}&date=${date}`);
    const data = await res.json();
    return data.slots; // Asegúrate de que el backend devuelve { slots: [...] }
}

// Reservar un slot
export async function bookSlot(commonAreaId: number, date: string, hour: number): Promise<boolean> {
    const res = await fetch(`${API_URL}/reserve`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ commonArea: commonAreaId, date, hour })
    });
    const data = await res.json();
    return data.success;
}
