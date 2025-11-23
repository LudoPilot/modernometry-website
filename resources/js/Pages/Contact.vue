<template>
	<div>
		<h1>Contact</h1>
		<p>Contactez-moi</p>

		<form
			id="contactForm"
			@submit="checkForm"
			action=""
			method="post"
		>
			<!--TODO : mettre des placeholders ou des floating labels / Ajouter Regex ou autres sécurités -->
			<div class="form-group mb-3">
				<label :for="label">{{ label }}</label>
				<input type="text" name="" id="" class="form-control" :class="{ 'is-invalid': error }" :name="label" :placeholder="placeholder" :value="modelValue" @input="onInput">
			</div>

			<!-- Message d'erreur-->
			<div>
				<small class="text-danger">{{ error }}</small>
			</div>

			<div>
				<label for="">Prénom</label>
				<input type="text" name="" id="">
			</div>

			<div>
				<label for="">Numéro de téléphone (facultatif)</label>
				<input type="tel" name="" id="">
			</div>
			<div>
				<label for="">Adresse e-mail</label>
				<input type="email" name="" id="">
			</div>

			<div>
				<textarea name="" id="" cols="30" rows="10" placeholder="Votre message"></textarea>
			</div>

			<div>
				<input type="checkbox" name="newsletter_subscription" id="newsletterSubscription">
				<label for="newsletterSubscription">S'abonner à la newsletter ?</label>
			</div>

			<button type="submit" @click="submit"></button>
		</form>
	</div>
</template>

<script setup>
	const props = defineProps({
		label: String,
		type: {
			type: String,
			default: 'text',
		},
		modelValue: String,
		placeholder: String, 
		error: String,
	});

	const emit = defineEmits(['update:modelValue']);

	const onInput = ($event) => {
		emit('update:modelValue', $event.target.value);
	};
</script>