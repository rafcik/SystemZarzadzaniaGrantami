<?php
echo '<div id="tabContainer" class="zakladki">';
require_once('tabs_layout.php');

for($i = 0; $i < count($Grant_item->podwykonawcyUserModel); $i++)
{
    echo '<a class="zakladka" href="' . base_url() . 'grant/get/' . $Grant_item->id . '/' . $Grant_item->zakladki[$i]['id'] . '">' . $Grant_item->zakladki[$i]['nazwa'] . '</a>';
    // echo '<br />Opis: ' . $Grant_item->zakladki[$i]['opis'];
    // echo '<br />Właściciel podzakladki: ' . $Grant_item->podwykonawcyUserModel[$i]->imie . ' ' . $Grant_item->podwykonawcyUserModel[$i]->nazwisko . ' - ' . $Grant_item->podwykonawcyUserModel[$i]->email;
}
echo '</div>';
?>
<br />
<br />
<br />
<h3 id="tabName"></h3>

<form id="delete_form" method="post" action="<?php echo base_url() . 'grant/'; ?>delete_tab">
    <input type="hidden" name="idGrant" value="<?php echo $Grant_item->id; ?>" />
    <input type="hidden" name="idZakladki" value="<?php echo $idZakladki; ?>" />
    <input type="submit" value="Usuń zakładkę" />
</form>
