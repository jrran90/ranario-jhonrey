<script setup lang="ts">
import {computed, onMounted, ref} from "vue";
import axios from "axios";

const isOpen = ref(false);
const nextOpening = ref('');
const selectedDate = ref(new Date());

onMounted(async () => {
    await getStoreStatus();
})

const status = computed(() => isOpen.value ? 'open' : 'closed');

const getStoreStatus = async () => {
    const res = await axios.get('store-status');
    isOpen.value = res.data.is_open;
    nextOpening.value = res.data.next_opening;
}

const checkDate = async () => {
    let res = await axios.get('next-opening', {
        params: {date: selectedDate.value}
    });
    isOpen.value = res.data.is_open;
    nextOpening.value = res.data.next_opening;
}
</script>

<template>
    <div class="w-1/2 mx-auto mt-10">
        <h2 class="text-xl font-semibold mb-1">Amy and Dave Bakery</h2>
        <div>
            <label class="block font-semibold">Pick a date</label>
            <input
                type="date"
                v-model="selectedDate"
                @change="checkDate"
            />
            <div v-if="selectedDate" class="mt-5">
                <p>We are {{ status }}!</p>
                <p v-if="!isOpen"><strong>Next opening:</strong> {{ nextOpening }}</p>
            </div>
        </div>
    </div>
</template>
