let drugList = [];

function changeMainImage(src) {
  const mainImg = document.getElementById("main-image");
  mainImg.src = src;

  document.querySelectorAll(".thumbnail").forEach((thumb) => {
    thumb.classList.remove("active");
  });

  event.target.classList.add("active");
}

document.addEventListener("DOMContentLoaded", () => {
  const addBtn = document.getElementById("add-drug");
  const drugInput = document.getElementById("drug");
  const qtyInput = document.getElementById("quantity");

  addBtn.addEventListener("click", () => {
    const drug = drugInput.value.trim();
    const qty = parseInt(qtyInput.value);
    const price = parseFloat(window.drugPrices[drug]);

    if (!window.drugPrices.hasOwnProperty(drug)) {
      alert("Please select a valid drug from the list.");
      return;
    }

    if (!drug || isNaN(qty) || qty <= 0 || qty > 1000 || isNaN(price)) {
      alert("Please enter a valid quantity (1–1000).");
      return;
    }

    const existingIndex = drugList.findIndex((d) => d.drug === drug);
    if (existingIndex !== -1) {
      drugList[existingIndex].qty += qty;
    } else {
      drugList.push({ drug, qty, price });
    }

    renderQuotationBox();
    drugInput.value = "";
    qtyInput.value = "";
  });

  console.log("send_quotation.js loaded");
});

function deleteDrug(index) {
  drugList.splice(index, 1);
  renderQuotationBox();
}

function renderQuotationBox() {
  const container = document.getElementById("quotation-items");
  const totalEl = document.getElementById("grand-total");

  container.innerHTML = "";
  let total = 0;

  drugList.forEach((d, i) => {
    const rowTotal = d.qty * d.price;
    total += rowTotal;

    const row = document.createElement("div");
    row.className = "quotation-item";
    row.innerHTML = `
  <span>${d.drug}</span>
  <span>${d.price.toFixed(2)} x ${d.qty}</span>
  <span>${rowTotal.toFixed(2)}</span>
  <button class="remove-btn" onclick="deleteDrug(${i})">X</button>
`;

    container.appendChild(row);
  });

  totalEl.textContent = total.toFixed(2);
  document.getElementById("drugs_json").value = JSON.stringify(drugList);
}

function validateBeforeSubmit() {
  if (drugList.length === 0) {
    alert("Please add at least one drug to the quotation.");
    return false;
  }

  return true;
}
