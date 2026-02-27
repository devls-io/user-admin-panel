function showModal(type, message, callback = null) {
  // 1. Criar a "Mãe" (Overlay)
  const overlay = document.createElement("div");
  overlay.classList.add("modal");

  // 2. Criar a "Box" (Conteúdo)
  const box = document.createElement("div");
  box.classList.add("modal-content");

  // 3. Criar a Mensagem
  const h3 = document.createElement("h3");
  h3.innerText = message;
  h3.classList.add("modal-message");
  box.appendChild(h3);

  // Fechar ao clicar no fundo escuro
  overlay.onclick = (e) => {
    if (e.target === overlay) overlay.remove();
  };

  // 4. Lógica de botões por tipo
  if (type === "alert") {
    const btnOk = document.createElement("button");
    btnOk.classList.add("btnOk");
    btnOk.innerText = "OK";
    btnOk.onclick = () => {
      if (callback) callback();
      overlay.remove();
    };

    box.appendChild(btnOk);
  }

  if (type === "confirm") {
    const btnConfirm = document.createElement("button");
    btnConfirm.classList.add("confirm-btn");
    btnConfirm.innerText = "Sim";

    btnConfirm.onclick = () => {
      if (callback) callback();
      overlay.remove();
    };

    const btnClose = document.createElement("button");
    btnClose.classList.add("cancel-btn");
    btnClose.innerText = "Não";
    btnClose.onclick = () => overlay.remove();

    // Anexando um por um
    box.appendChild(btnConfirm);
    box.appendChild(btnClose);
  }

  // 5. Montagem Final e Exibição
  overlay.appendChild(box);
  document.body.appendChild(overlay);

  // Pequeno delay para a transição de opacidade do seu CSS brilhar
  setTimeout(() => overlay.classList.add("show"), 10);
}
