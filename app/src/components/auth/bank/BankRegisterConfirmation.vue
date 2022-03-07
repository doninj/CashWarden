<script setup lang="ts">
import FormHeader from "@/components/auth/FormHeader.vue"


function goToApp() {
  window.open("http://localhost:3000", "_blank")
  window.close()
}
</script>

<template>
  <div class="container">
    <FormHeader title="Tous est bon !" subtitle="Vous pouvez désormais fermez cette fênetre"/>
    <div class="body-container">
      <div class="wrapper">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             viewBox="0 0 98.5 98.5" enable-background="new 0 0 98.5 98.5" xml:space="preserve">
  <path class="checkmark" fill="none" stroke-width="8" stroke-miterlimit="10" d="M81.7,17.8C73.5,9.3,62,4,49.2,4
	C24.3,4,4,24.3,4,49.2s20.3,45.2,45.2,45.2s45.2-20.3,45.2-45.2c0-8.6-2.4-16.6-6.5-23.4l0,0L45.6,68.2L24.7,47.3"/>
</svg>
      </div>
      <Button type="primary" class="home-button" @click="goToApp" label="Aller à l'application" />
    </div>
  </div>


</template>

<style scoped lang="scss">
.container {
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;

  .body-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
  }

  .home-button {
    margin-top: auto;
  }
}
.wrapper {
  width: 100px;
  margin: 4em auto 0;
}

.checkmark {
  stroke: #6366F1;
  stroke-dashoffset: 745.74853515625;
  stroke-dasharray: 745.74853515625;
  animation: dash 2s ease-out forwards;
}

@keyframes dash {
  0% {
    stroke-dashoffset: 745.74853515625;
  }
  100% {
    stroke-dashoffset: 0;
  }
}

.fulfilling-square-spinner , .fulfilling-square-spinner * {
  box-sizing: border-box;
}

.fulfilling-square-spinner {
  margin: 4em auto 0;
  height: 80px;
  width: 80px;
  position: relative;
  border: 8px solid #6366F1;
  animation: fulfilling-square-spinner-animation 4s infinite ease;
}

.fulfilling-square-spinner .spinner-inner {
  vertical-align: top;
  display: inline-block;
  background-color: #6366F1;
  width: 100%;
  opacity: 1;
  animation: fulfilling-square-spinner-inner-animation 4s infinite ease-in;
}

@keyframes fulfilling-square-spinner-animation {
  0% {
    transform: rotate(0deg);
  }

  25% {
    transform: rotate(180deg);
  }

  50% {
    transform: rotate(180deg);
  }

  75% {
    transform: rotate(360deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

@keyframes fulfilling-square-spinner-inner-animation {
  0% {
    height: 0%;
  }

  25% {
    height: 0%;
  }

  50% {
    height: 100%;
  }

  75% {
    height: 100%;
  }

  100% {
    height: 0%;
  }
}
</style>
