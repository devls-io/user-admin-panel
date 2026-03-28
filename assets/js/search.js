let debounceTimer;

const inputBusca = document.getElementById("input-busca");
const listaUsuarios = document.getElementById("lista-usuarios");
const paginacao = document.getElementById("paginacao");

inputBusca.addEventListener("input", () => {
  clearTimeout(debounceTimer);

  // Busca depois de 300ms
  debounceTimer = setTimeout(async () => {
    const termo = inputBusca.value;

    const novaUrl = `index.php?pagina=1&busca=${encodeURIComponent(termo)}`;

    window.history.pushState({ path: novaUrl }, "", novaUrl);

    // O FETCH
    // Vamos pedir a página toda mas usar apenas alguns pedaços

    try {
      const response = await fetch(novaUrl);

      if (!response.ok) throw new Error("Erro na requisição");

      const html = await response.text();

      const parser = new DOMParser();
      const doc = parser.parseFromString(html, "text/html");

      const novaLista = doc.getElementById("lista-usuarios");
      const novaPaginacao = doc.getElementById("paginacao");

      // Substituir

      listaUsuarios.innerHTML = novaLista
        ? novaLista.innerHTML
        : '<p id="aviso">Nenhum usuário encontrado 😅</p>';
      paginacao.innerHTML = novaPaginacao ? novaPaginacao.innerHTML : "";
    } catch (error) {
      console.error("Ops! Problemas na busca:", error);
    }
  }, 300);
});
