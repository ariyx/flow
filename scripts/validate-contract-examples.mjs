import { readdir, readFile } from 'node:fs/promises'
import { fileURLToPath } from 'node:url'
import { join, relative } from 'node:path'

const examplesDirectory = fileURLToPath(
  new URL('../packages/contracts/examples/', import.meta.url),
)

async function findExamples(directory) {
  const entries = await readdir(directory, { withFileTypes: true })
  const files = await Promise.all(
    entries
      .filter((entry) => !entry.name.startsWith('.'))
      .map(async (entry) => {
        const path = join(directory, entry.name)

        if (entry.isDirectory()) {
          return findExamples(path)
        }

        return [path]
      }),
  )

  return files.flat()
}

const examples = await findExamples(examplesDirectory)

if (examples.length === 0) {
  console.log('No contract examples to validate.')
  process.exit(0)
}

for (const example of examples) {
  if (!example.endsWith('.json')) {
    throw new Error(`Unsupported contract example format: ${relative(process.cwd(), example)}`)
  }

  JSON.parse(await readFile(example, 'utf8'))
}

console.log(`Validated ${examples.length} JSON contract example(s).`)
