<script>
    export let labels;
    export let className;
    export let action;
    export let csrf;

    let doModal = false;

    /**
     * Perform original action.
     */
    async function doAction() {
        doModal = false;
        //window.location = action;
        const response = await fetch(action, {
            method: 'POST',
            credentials: 'include',
            body: JSON.stringify({
                csrf: csrf,
            })
        });
    }

</script>

{#if doModal }
    <div class="">
        <div class="dialog-modal dialog-confirm">
            <p>{ labels['Are you sure you want to delete this implantation ?'] }</p>
            <p>{ labels['All data such as classes attached to it will be deleted too !'] }</p>
            <div>
                <button class="button-ok" on:click={ () => doAction() }>{ labels['Yes'] }</button>
                <button class="button-cancel" on:click={ () => doModal = false }>{ labels['No'] }</button>
            </div>
        </div>
    </div>
{/if}

<a href="/#" on:click|preventDefault={ () => doModal = true }>
    <i class="{className}"></i>
</a>