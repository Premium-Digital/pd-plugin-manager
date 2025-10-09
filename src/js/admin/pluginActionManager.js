import $ from 'jquery';

export function handleButtonsActions() {
    $('body').on('click', '.pd-plugin-card__button', function(e) {
    e.preventDefault();
    $(this).closest(".pd-plugin-card").addClass("loading");
    const button = $(this);
    const action = $(button).data('action');
    const repo_url = $(button).data('repo-url') ? $(button).data('repo-url') : null;
    const plugin_file = $(button).data('plugin-file') ? $(button).data('plugin-file') : null;
    
    $.post(PDPluginManager.ajax_url, {
        action: 'pd_plugin_action',
        nonce: PDPluginManager.nonce,
        plugin_repo: repo_url,
        plugin_action: action,
        plugin_file: plugin_file
    }).done((response) => {
        console.log(response);
        if (response.success) {
            alert("Plugin " + response.data.status);
            location.reload();
        } else {
            alert('Wystąpił błąd: ' + response.data);
        }
    }).fail(() => {
        alert('Wystąpił błąd podczas komunikacji z serwerem.');
    });
});

}
