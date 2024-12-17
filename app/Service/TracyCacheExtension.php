<?php

declare(strict_types = 1);

namespace App\Service;

use Tracy\IBarPanel;

final class TracyCacheExtension implements IBarPanel
{
    public function getTab()
    {
        return
            '<span title="Vysvětlující popisek">
                <img src="https://img.icons8.com/ios/50/undefined/delete--v1.png" alt="Trash cache" height="15" width="15" style="display: inline;">
                <span class="tracy-label">Cache</span>
            </span>';
    }

    public function getPanel()
    {
        return
            '<h1>Cache</h1>
            <script>
              function deleteCache (link) {
                try {
                  fetch(location.origin + location.pathname + "/homepage/" + link)
                  .then(reply => {
                      if (reply.status) {
                          document.getElementById("trash-cache").innerText = "Smazáno"
                          document.getElementById("trash-cache").classList.replace("bg-danger", "bg-success")
                      }
                  })
                } catch (err) {
                  console.log(err)
                }
              }
            </script>
            <div class="tracy-inner">
                <div class="tracy-inner-container">
                    <img src="https://img.icons8.com/ios/50/undefined/delete--v1.png" alt="Trash cache" height="15" width="15" style="display: inline;">
                    <button id="trash-cache" onclick="deleteCache(`delete-cache`)" class="bg-danger p-1 font-bold text-white">Smazat cache</button>
                </div>
            </div>';
    }
}