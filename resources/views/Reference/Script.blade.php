<script defer>
    const owner_model = document.querySelector('#owner_model')
    const owner_model_display_aid = document.querySelector("#owner_model_display_aid")
    const references_form = document.querySelector('#references_form')
    owner_model.addEventListener('change', (e) => references_form.submit())
    owner_model_display_aid.addEventListener('change', (e) => references_form.submit())

    console.log(owner_model)

</script>
