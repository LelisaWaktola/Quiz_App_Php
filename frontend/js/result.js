fetch('../backend/j/get_result.php')
.then(response => response.json())
.then(data => {
  if (data.success) {
    document.getElementById('score').textContent =
      `You scored ${data.score} out of ${data.total} on ${data.time}`;
  } else {
    document.getElementById('score').textContent = "No result available.";
  }
})
.catch(err => {
  document.getElementById('score').textContent = "Error fetching result.";
  console.error(err);
});