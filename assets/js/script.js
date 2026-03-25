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

    //Captura TUDO (nome, email e a imagem do avatar)
    const formData = new FormData(insertForm);

    if (formData.get("nome") && formData.get("email")) {
      sendUserData(formData);
    }
  });
}

const sendUserData = async (formData) => {
  try {
    const response = await fetch("api/insert.php", {
      method: "POST",
      // Sem JSON dessa vez enviamos com formData
      body: formData,
    });

    const resultado = await response.json();

    if (!response.ok) {
      showModal("alert", `Não foi possivel cadastrar: ${resultado.erro}`);
    } else {
      showModal("alert", resultado.mensagem);

      insertForm.reset();
      document.querySelector(".avatar-preview img").src =
        "assets/uploads/avatars/default-avatar.png";
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
    const formData = new FormData(editForm);

    // Adicionar o id ao formData

    formData.append("id", id);

    // Função para editar

    if (formData.get("nome") && formData.get("email")) {
      sendEditUserData(formData);
    }
  });
}

const sendEditUserData = async (formData) => {
  try {
    const response = await fetch("api/update.php", {
      method: "POST",
      body: formData,
    });

    const resultado = await response.json();

    if (!response.ok) {
      showModal("alert", `Resultado da Edição: ${resultado.erro}`);
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

// Lógica do preview da imagem

const avatarInput = document.getElementById("avatar");
const previewImage = document.querySelector(".avatar-preview img");

if (avatarInput && previewImage) {
  avatarInput.addEventListener("change", (e) => {
    const file = e.target.files[0]; // O primeiro arquivo

    if (file) {
      const extensoesPermitidas = [
        "image/jpeg",
        "image/png",
        "image/jpg",
        "image/webp",
      ];

      if (!extensoesPermitidas.includes(file.type)) {
        showModal(
          "alert",
          "Ei! Isso não é uma imagem válida. Escolha um JPG, PNG, JPEG ou WebP",
        );
        e.target.value = "";
        return;
      }

      const url = URL.createObjectURL(file);
      previewImage.src = url;
    }
  });
}
