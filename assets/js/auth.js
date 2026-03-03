const loginForm = document.getElementById("loginForm");

loginForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const email = document.getElementById("email").value;
  const senha = document.getElementById("senha").value;

  if (email && senha) {
    // Função para verificar
    handleLogin(email, senha);
  }
});

const handleLogin = async (email, senha) => {
  try {
    const response = await fetch("api/login_handler.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email, senha }),
    });

    const resultado = await response.json();

    if (!response.ok) {
      showModal("alert", resultado.erro);
    } else {
      window.location.href = "index.php";
    }
  } catch (error) {
    showModal(
      "alert",
      "Houve um erro de conexão com o servidor. Tente novamente.",
    );
    console.error("Erro na requisição: ", error);
  }
};

// Lógica para o botão de mostrar e ocultar senha

const togglePassword = document.querySelector("#togglePassword");
const senhaInput = document.querySelector("#senha");

if (togglePassword && senhaInput) {
  togglePassword.addEventListener("click", () => {
    const type =
      senhaInput.getAttribute("type") === "password" ? "text" : "password";
    senhaInput.setAttribute("type", type);

    togglePassword.setAttribute(
      "title",
      type === "password" ? "mostrar senha" : "esconder senha",
    );

    // Mudar o icone
    togglePassword.textContent = type === "password" ? "👁️" : "👁️‍🗨️";
  });
}
