<template>
  <div class="modal-overlay" v-if="isOpen">
    <div class="modal-content bg-white rounded shadow-lg" style="max-width: 600px; width: 100%;">
      
      <!-- HEADER -->
      <div class="modal-header bg-teal text-white p-3 d-flex justify-content-between">
        <h5 class="m-0 fw-bold">{{ step == 1 ? 'Enter Your Information' : 'Add E-Signature' }}</h5>
        <button @click="$emit('close')" class="btn-close btn-close-white"></button>
      </div>

      <!-- STEP 1: INFO -->
      <div v-if="step == 1" class="modal-body p-4">
        <div class="mb-3">
          <label class="fw-bold text-muted small">Name</label>
          <input v-model="signerInfo.name" type="text" class="form-control border-bottom-only">
        </div>
        <div class="mb-3">
          <label class="fw-bold text-muted small">Title</label>
          <input v-model="signerInfo.title" type="text" class="form-control border-bottom-only">
        </div>
        <div class="mb-3">
          <label class="fw-bold text-muted small">Date</label>
          <input :value="currentDate" disabled type="text" class="form-control border-bottom-only bg-white">
        </div>
        <div class="d-flex justify-content-end mt-4">
          <button @click="goToStep2" class="btn btn-teal text-white fw-bold px-4">NEXT</button>
        </div>
      </div>

      <!-- STEP 2: SIGNATURE -->
      <div v-if="step == 2" class="modal-body p-4">
        <!-- Tabs -->
        <div class="d-flex mb-3 border rounded">
          <button class="btn flex-fill" :class="tab == 'draw' ? 'btn-teal text-white' : 'bg-light'" @click="tab = 'draw'">Draw It</button>
          <button class="btn flex-fill" :class="tab == 'type' ? 'btn-teal text-white' : 'bg-light'" @click="tab = 'type'">Type It</button>
        </div>

        <!-- Draw Pad -->
        <div v-show="tab == 'draw'" class="border rounded" style="height: 200px; background: #f8f9fa;">
           <VueSignaturePad ref="signaturePad" width="100%" height="200px" :options="{ penColor: '#000' }" />
        </div>

        <!-- Type Pad -->
        <div v-show="tab == 'type'" class="border rounded d-flex align-items-center justify-content-center" style="height: 200px; background: #f8f9fa;">
           <h2 style="font-family: 'Brush Script MT', cursive; font-size: 3rem;">{{ signerInfo.name }}</h2>
        </div>

        <div class="d-flex justify-content-between mt-4">
             <button v-if="tab == 'draw'" @click="clearPad" class="btn btn-outline-secondary btn-sm">Clear</button>
             <div></div> <!-- Spacer -->
             <div class="d-flex gap-2">
                 <button @click="step = 1" class="btn btn-light">Back</button>
                 <button @click="submitSignature" class="btn btn-teal text-white fw-bold px-4">SUBMIT & SIGN</button>
             </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps(['isOpen'])
const emit = defineEmits(['close', 'signed'])

const step = ref(1)
const tab = ref('draw')
const signaturePad = ref(null)

const signerInfo = ref({ name: '', title: '' })
const currentDate = new Date().toLocaleDateString()

const goToStep2 = () => {
    if(!signerInfo.value.name || !signerInfo.value.title) return alert("Please fill all fields");
    step.value = 2;
}

const clearPad = () => signaturePad.value.clearSignature();

const submitSignature = () => {
    let signatureImage = null;

    if (tab.value == 'draw') {
        const { isEmpty, data } = signaturePad.value.saveSignature();
        if (isEmpty) return alert("Please sign first");
        signatureImage = data;
    } else {
        // Simple Canvas generation for Typed signature
        const canvas = document.createElement('canvas');
        canvas.width = 500; canvas.height = 150;
        const ctx = canvas.getContext('2d');
        ctx.font = "60px 'Brush Script MT'";
        ctx.fillText(signerInfo.value.name, 50, 100);
        signatureImage = canvas.toDataURL();
    }

    emit('signed', { ...signerInfo.value, signatureImage });
}
</script>

<style scoped>
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1050; }
.btn-teal { background-color: #17a2b8 !important; border-color: #17a2b8; }
.border-bottom-only { border: none; border-bottom: 2px solid #ddd; border-radius: 0; padding-left: 0; }
.border-bottom-only:focus { box-shadow: none; border-color: #17a2b8; }
.bg-teal{background-color: #17a2b8 !important;}
</style>