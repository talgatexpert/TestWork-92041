<template>

	<v-card width="500" class="mx-auto mt-15 my-auto">
		<v-alert
				transition="scroll-x-reverse-transition"
				dense
				:value="alert"
				prominent
				:type="type"
		>{{ errors.message }}
		</v-alert>
		<v-form ref="form"
		        class="py-10 px-15 w-100">
			<v-text-field
					v-model="form.email"
					label="Email"
					required
					:rules="rules.email"
					:error-messages="errors.email"
			></v-text-field>
			<v-text-field
					v-model="form.password"
					type="password"
					label="Password"
					:rules="rules.password"
					:error-messages="errors.password"
					required
			></v-text-field>
			<div class="d-flex align-center justify-lg-space-between">

				<v-btn to="/register" depressed plain class="pl-0 text-decoration-underline" text>Create an account</v-btn>
				<v-btn :disabled="processing" outlined @click="login">Login</v-btn>
			</div>
		</v-form>
	</v-card>
</template>

<script>


import {sanctum} from "@/api/request";
import {login} from "@/api/auth";
import {setToken} from "@/utils/auth";

export default {
	name: 'Login',
	data() {
		return {
			valid: true,
			rules: {
				email: [
					v => !!v || 'E-mail is required',
					v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
				],
				password: [
					v => !!v || 'Password is required',
					v => (v && v.length <= 10) || 'Password must be less than 10 characters',
				],
			},
			form: {
				email: '',
				password: '',
			},
			alert: false,
			processing: false,
			errors: {},
			type: 'success'
		}
	},
	methods: {

		async login() {
			this.processing = true
			this.errors = {}
			await sanctum()
			await login(this.form).then(async ({data}) => {
				await this.$store.dispatch('auth/login', data.user)
				setToken(data.token, true)
				this.alert = false;
				this.type = 'success'
				await this.$router.push('/home')
			}).catch(({response: {data}}) => {
				this.alert = true;
				this.type = 'error'
				this.errors = data?.errors ?? {}
				this.errors['message'] = data.message
			}).finally(() => {
				this.processing = false
			})
		},
	}
};
</script>
