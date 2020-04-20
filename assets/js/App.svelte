<script>
    import { onMount } from 'svelte';
    import UserAdd from './components/UserAdd.svelte';

    let apiBaseUrl = 'https://127.0.0.1:8000/api';

    onMount(() => {
        console.log('Hello, Symfony + Svelte!');
        console.log(getLocale());
    });


    /**
     * Get the translated strings to use.
     */
    async function getLocale() {
        const response = await fetch(`${apiBaseUrl}/locale/forms/user.label.first-name`, {
            method : 'GET',
            credentials : 'include'
        });

        return response.json();
    }

</script>

<h1>Symfony et Svelte</h1>

<style>
    h1 {
        text-align: center;
        color: #D26D26;
    }
</style>

<UserAdd />