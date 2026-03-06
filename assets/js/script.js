const allDeleteButtons = document.querySelectorAll("button[data-id]");

allDeleteButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const id = button.dataset.id;

    const nome = button.dataset.name;

    showModal(
      "confirm",
      `Certeza que deseja excluir o registro de ${nome} ?`,
      () => sendDelete(id, button),
    );
  });
});

const sendDelete = async (id, button) => {
  try {
    const response = await fetch("api/delete.php", {
      method: "POST",
      headers: { "Content-type": "Application/json" },
      body: JSON.stringify({ id_para_excluir: id }),
    });

    // Recebemos a resposta enviada pelo servidor
    const resultado = await response.json();

    if (!response.ok) {
      showModal("alert", `Erro ao excluir: ${resultado.erro}`);
    } else {
      const liToRemove = button.closest("li");
      liToRemove.remove();
      checkEmptyList();
      // alert(resultado.mensagem);
      showModal("alert", resultado.mensagem);
    }
  } catch (error) {
    showModal(
      "alert",
      "Houve um erro de conexão com o servidor. Tente novamente.",
    );
    console.error("Erro na requisição: ", error);
  }
};

// Codigo para inserir um novo registro

const insertForm = document.getElementById("insertForm");

if (insertForm) {
  insertForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const campoNome = document.getElementById("nome").value;
    const campoEmail = document.getElementById("email").value;

    if (campoNome && campoEmail) {
      // Função para executar o insert
      sendUserData(campoNome, campoEmail);
    }
  });
}

const sendUserData = async (nome, email) => {
  try {
    const response = await fetch("api/insert.php", {
      method: "POST",
      headers: { "Content-Type": "Application/json" },
      body: JSON.stringify({ nome, email }),
    });

    const resultado = await response.json();

    if (!response.ok) {
      showModal("alert", `Não foi possivel cadastrar: ${resultado.erro}`);
    } else {
      showModal("alert", resultado.mensagem);

      insertForm.reset();
    }
  } catch (error) {
    showModal(
      "alert",
      "Houve um erro de conexão com o servidor. Tente novamente.",
    );
    console.error("Erro na requisição: ", error);
  }
};

// Script para garantir que a página atualize ao usar as setas do navegador

window.addEventListener("pageshow", (e) => {
  if (e.persisted) {
    window.location.reload();
  }
});

// Lógica para edição:

const editForm = document.getElementById("editForm");

if (editForm) {
  editForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const id = editForm.dataset.id;
    const campoNome = document.getElementById("nome").value;
    const campoEmail = document.getElementById("email").value;

    // Função para editar

    if (campoNome && campoEmail) {
      sendEditUserData(id, campoNome, campoEmail);
    }
  });
}

const sendEditUserData = async (id, nome, email) => {
  try {
    const response = await fetch("api/update.php", {
      method: "POST",
      headers: { "Content-Type": "Application/json" },
      body: JSON.stringify({ id, nome, email }),
    });

    const resultado = await response.json();

    if (!response.ok) {
      showModal("alert", `Erro na edição: ${resultado.erro}`);
    } else {
      showModal("alert", resultado.mensagem, () => {
        window.location.replace("index.php");
      });
    }
  } catch (error) {
    showModal(
      "alert",
      "Houve um erro de conexão com o servidor. Tente novamente.",
    );
    console.error("Erro na requisição: ", error);
  }
};

const checkEmptyList = () => {
  const items = document.querySelectorAll("ul#lista-usuarios li");

  if (items.length === 0) {
    const emptyMessage = document.createElement("div");
    emptyMessage.innerHTML =
      "<p>Lista vazia 😅, fique a vontade para inserir um registro</p>";

    // Localiza o seu título
    const titulo = document.querySelector("h1");

    // Insere a div logo após o h1
    titulo.after(emptyMessage);
  }
};

// Tema Claro e Escuro

const btnThemeToggle = document.getElementById("theme-toggle");

btnThemeToggle.addEventListener("click", () => {
  document.body.classList.toggle("dark");

  const novoTema = document.body.classList.contains("dark") ? "dark" : "light";

  btnThemeToggle.innerHTML =
    novoTema === "dark" ? "Alterar Tema ☀️" : "Alterar Tema 🌙 ";

  document.cookie = `theme=${novoTema}; max-age=${30 * 24 * 60 * 60}; path=/`;
});
