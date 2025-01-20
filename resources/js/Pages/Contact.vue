<template>
	<div>
		<h1>Contact</h1>
		<p>Contactez-moi</p>

		<form>
			<!--TODO : mettre des placeholders ou des floating labels / Ajouter Regex ou autres sécurités -->
			<div>
				<label for="">Nom</label>
				<input type="text" name="" id="">
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

			<button type="submit"></button>
		</form>
	</div>
</template>
