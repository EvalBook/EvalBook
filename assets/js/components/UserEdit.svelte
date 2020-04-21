<script>
    import { Language } from '../api';

    let firstName;
    let lastName;
    let email;
    let labels;

    // Fetching interface strings.
    labels = Language.getStrings('forms', [
        'user.label.first-name',
        'user.label.last-name',
        'user.label.email',
        'common.yes',
        'common.no'
    ]);

</script>

{#await labels}
    <p>Waiting strings</p>
{:then labels}
    <div class="row mt-3">
        <div class="col-md-6">
            <label for="firstName">{ labels['user.label.first-name'] }</label>
            <input class="form-control" type="text" name="firstName" id="firstName" bind:value={firstName} />
        </div>

        <div class="col-md-6">
            <label for="lastName">{ labels['user.label.last-name'] }</label>
            <input type="text" class="form-control" name="lastName" id="lastName" bind:value={lastName} />
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label for="email">{ labels['user.label.email'] }</label>
            <input class="form-control" type="email" name="email" id="email" bind:value={email} />
        </div>

        <div class="col-md-6">
            <label for="active">{ labels['user.label.email'] }</label>
            <select class="form-control" name="active" id="active">
                <option value="true">{ labels['common.yes'] }</option>
                <option value="false">{ labels['common.no'] }</option>
            </select>
        </div>
    </div>

{/await}