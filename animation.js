document.addEventListener("DOMContentLoaded", () => {
    const header = document.getElementById("header");
    const duration = 5000; // Animation duration in milliseconds
    const steps = 100; // Number of steps
    const interval = duration / steps; // Interval for each step
    let step = 0;
  
    const intervalId = setInterval(() => {
      step++;
      const greenValue = Math.min(255, Math.floor((step / steps) * 255));
      document.body.style.backgroundColor = `rgb(255, ${greenValue}, 255 - ${greenValue})`;
  
      if (greenValue === 255) {
        header.classList.add("white");
      }
  
      if (step >= steps) {
        clearInterval(intervalId);
      }
    }, interval);
  });
  