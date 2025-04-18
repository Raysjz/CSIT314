import express from "express";
import helmet from "helmet";
import morgan from "morgan";
import cors from "cors";
import dotenv from "dotenv";
import { neon } from "@neondatabase/serverless";
import productRoutes from "./routes/productRoutes.js";

dotenv.config();

const app = express();
const PORT = process.env.PORT || 3000;

// Connect to Neon
const sql = neon(process.env.DATABASE_URL);

app.use(express.json());
app.use(cors());
app.use(helmet());
app.use(morgan("dev"));

// All routes starting with /api/products will go through productRoutes
app.use("/api/products", productRoutes);


// Example API route that queries Neon DB
app.get("/api/version", async (req, res) => {
  try {
    const result = await sql`SELECT version()`;
    res.json({ version: result[0].version });
  } catch (error) {
    console.error("❌ Database error:", error.message);
    res.status(500).json({ error: error.message });
  }
});

app.get("/", (req, res) => {
  res.send("Welcome to the API!");
});


app.get("/test", (req, res) => {
  res.send("Hello from the test route");
});

app.listen(PORT, () => {
  console.log("🚀 Server is running on port " + PORT);
});
