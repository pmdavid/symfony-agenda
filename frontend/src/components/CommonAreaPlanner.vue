<template>
  <div class="planner">
    <h2>Reservar Espacios Comunes</h2>

    <!-- Dropdown de Common Areas -->
    <label for="commonArea">Selecciona un espacio:</label>
    <select id="commonArea" v-model="selectedCommonArea">
      <option v-for="area in commonAreas" :key="area.id" :value="area">
        {{ area.name }}
      </option>
    </select>

    <!-- Selección de fecha -->
    <label for="date">Selecciona un día:</label>
    <input type="date" id="date" v-model="selectedDate" />

    <!-- Slots -->
    <div class="slots">
      <div
          v-for="slot in slots"
          :key="slot.hour"
          :class="['slot', slot.status.toLowerCase()]"
          @click="slot.status === 'LIBRE' && handleBook(slot)"
      >
        {{ slot.hour }}:00 - {{ slot.status }}
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, watch } from 'vue';
import { fetchAvailability, bookSlot } from '../api';

interface CommonArea {
  id: number;
  name: string;
}

interface Slot {
  hour: number;
  status: "LIBRE" | "RESERVADO";
}

export default defineComponent({
  name: 'CommonAreaPlanner',
  setup() {

    // Esto lo he hardcodeado por ahorrar tiempo, pero por norma general deberia venir de API, en una llamada especifica para obtener estos datos, o como parte de una llamada inicial donde se obtengan otros datos
    const commonAreas = ref<CommonArea[]>([
      { id: 1, name: 'Pista de Pádel' },
      { id: 2, name: 'Piscina' },
      { id: 3, name: 'Gimnasio' },
    ]);

    const selectedCommonArea = ref<CommonArea | null>(commonAreas.value[0]);
    const selectedDate = ref<string>(new Date().toISOString().split('T')[0]);

    const slots = ref<Slot[]>([]);

    const loadSlots = async () => {
      if (!selectedCommonArea.value || !selectedDate.value) return;
      try {
        slots.value = await fetchAvailability(selectedCommonArea.value.id, selectedDate.value);
      } catch (err) {
        console.error('Error cargando slots:', err);
      }
    };

    const handleBook = async (slot: Slot) => {
      if (!selectedCommonArea.value || !selectedDate.value) return;
      try {
        const success = await bookSlot(selectedCommonArea.value.id, selectedDate.value, slot.hour);
        if (success) {
          await loadSlots(); // Refresca datos tras reservar
        } else {
          // Actualmente aqui no entrara porque se ha bloqueado interactuar con los slots reservados, pero por ejemplo podria mostrar un mensaje de error en un alert
        }
      } catch (err) {
        console.error('Error reservando slot:', err);
      }
    };

    onMounted(loadSlots);

    watch([selectedCommonArea, selectedDate], loadSlots);

    return {
      commonAreas,
      selectedCommonArea,
      selectedDate,
      slots,
      handleBook,
    };
  },
});
</script>

<style scoped>
.planner {
  max-width: 900px;
  margin: 2rem auto;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  padding: 1.5rem;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

h2 {
  text-align: center;
  margin-bottom: 1.5rem;
  color: #222;
}

label {
  display: block;
  margin-top: 1rem;
  margin-bottom: 0.25rem;
  font-weight: 600;
}

select,
input[type="date"] {
  width: 100%;
  max-width: 300px;
  padding: 0.5rem 0.75rem;
  font-size: 1rem;
  border-radius: 4px;
  border: 1px solid #ccc;
  margin-bottom: 1rem;
}

/* Grid de slots */
.slots {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 6px;
  grid-auto-rows: 80px;
  margin-top: 1rem;
}

/* Cada slot */
.slot {
  display: flex;
  align-items: center;
  justify-content: center; /* fuerza anchura uniforme visual */
  width: 100%;
  height: 100%;
  font-weight: bold;
  text-align: center;
  cursor: pointer;
  border-radius: 6px;
  border: 1px solid #ccc;
  box-sizing: border-box;
  user-select: none;
  transition: all 0.2s ease;
  color: #000;
  padding: 0 0.5rem; /* evita que texto muy corto reduzca visualmente el ancho */
}

.slot.libre {
  background-color: #d4f0d4;
}
.slot.libre:hover {
  background-color: #b6e6b6;
}

.slot.reservado {
  background-color: #f0d4d4;
  cursor: not-allowed;
}
</style>


