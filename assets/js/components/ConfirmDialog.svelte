<script>
    import { Language } from '../api';

    let labels;

    // Fetching interface strings.
    labels = Language.getStrings('templates', [
        'Are you sure you want to delete this item',
        'Yes',
        'No'
    ]);

</script>

<style>

</style>

{#await labels}
    <p>Chargement...</p>
{:then labels}
    <!--
    <div class="dialog dialog-confirm">
        <p>
            { labels['Are you sure you want to delete this item'] }
        </p>
        <button>{ labels['Yes'] }</button>
        <button>{ labels['No'] }</button>
    </div>
-->

    <a href="#/">
        <i class="fas fa-trash"></i>
    </a>

{/await}