// JS leve para interações do header (hambúrguer/menu) e afins
document.addEventListener('DOMContentLoaded', () => { // Aguarda a árvore de elementos estar pronta
  const btn = document.getElementById('hamburger'); // Seleciona o botão hambúrguer
  const nav = document.getElementById('primary-menu'); // Seleciona a navegação principal

  if (!btn || !nav) return; // Se algum elemento não existir, sai silenciosamente

  btn.addEventListener('click', () => { // Ao clicar no hambúrguer
    const open = nav.classList.toggle('is-open'); // Alterna a classe que exibe/oculta o menu
    btn.setAttribute('aria-expanded', open ? 'true' : 'false'); // Atualiza atributo ARIA para acessibilidade
  }); // Fim do handler de clique
}); // Fim do DOMContentLoaded
